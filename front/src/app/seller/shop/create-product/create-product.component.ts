import {AfterContentChecked, Component, OnInit} from '@angular/core';
import {AbstractControl, FormArray, FormBuilder, FormControl, Validators} from "@angular/forms";
import {Store} from "@ngrx/store";
import {addProduct, Product, Shop, Variant} from "@shared";
import {HttpClient} from "@angular/common/http";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
  selector: 'app-create-product',
  templateUrl: './create-product.component.html',
  styleUrls: ['./create-product.component.css']
})
export class CreateProductComponent implements OnInit {

  shopUuid: string;

  createProductFrom = this.fb.group({
    uuid: [''],
    name: ['', Validators.required],
    label: ['', Validators.required],
    pictures: new FormArray([]),
    price: [0, [Validators.required]],
    shopUuid: ['', Validators.required],
  })

  variants: Variant[] = [];

  constructor(
    private fb: FormBuilder,
    private store: Store<{shop: Shop, product: Product[]}>,
    private http: HttpClient,
    private router: Router,
    private route: ActivatedRoute,
  ) { }

  get pictures() {
    return this.createProductFrom.get('pictures') as FormArray;
  }

  addPicture(picture?: string ): void {
    this.pictures.push(new FormControl({file : new FormControl('', Validators.required)}));
  }

  setPicture(event: any, index): void {
    this.pictures.controls[index].setValue({file: event});
  }

  ngOnInit(): void {
    this.setShop();
    this.setProduct();
  }

  onSubmit(): void {
    if(this.createProductFrom.value.uuid) {
      this.http.patch('/api/shop/' + this.shopUuid +'/product', this.createProductFrom.value).subscribe((data: any) => {
        this.router.navigate(['/je-vends/ma-boutique/' + this.shopUuid + '/produits'])
        this.store.dispatch(addProduct({product: data}))
      })
    } else {
      this.http.post('/api/product', this.createProductFrom.value).subscribe((data: any) => {
        this.router.navigate(['/je-vends/ma-boutique/' + this.shopUuid + '/produits'])
        this.store.dispatch(addProduct({product: data}))
      })
    }
    console.log(this.createProductFrom.value);
  }

  setShop(): void {
    this.store.select('shop').subscribe((data: any) => {
      if (data.shop) {
        this.shopUuid = data.shop.uuid;
        this.createProductFrom.controls.shopUuid.setValue(data.shop.uuid);
      }
    });
  }

  setProduct(): void {
    this.route.params.subscribe((params) => {
      const uuid = params['puuid'];
      this.store.select('product').subscribe((data: any) => {
        const elem = data.products.find(elem => elem.uuid === uuid);
        if(elem) {
          this.createProductFrom.patchValue(elem);
          elem.pictures.forEach((picture: any, index) => {
            this.addPicture();
            this.setPicture(picture.file, index);
          })
        }
      })
    })
  }
}
