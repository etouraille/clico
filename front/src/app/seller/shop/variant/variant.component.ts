import { Component, OnInit } from '@angular/core';
import {FormArray, FormBuilder, FormControl, FormGroup} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";
import {Store} from "@ngrx/store";
import {Product} from "@shared";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-variant',
  templateUrl: './variant.component.html',
  styleUrls: ['./variant.component.css']
})
export class VariantComponent implements OnInit {

  variants = [];
  variantForm = new FormArray([]);
  product: Product;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private store: Store<{ product: Product}>,
    private http: HttpClient,
  ) { }

  ngOnInit(): void {
    this.setProduct();
    this.setVariants();
  }

  addVariant(ret?: any): void {
    if(!ret) {
      this.variantForm.push(new FormControl({id : '' , name : '', labels: []}));
    } else {
      this.variantForm.push(new FormControl({id : '', name : '', labels: ret}));
    }
  }

  removeVariant(index: number) {
    this.variantForm.controls.splice(index,1)
    this.variantForm.value.splice(index, 1);
  }

  addVariants(variants: any ) : void {
    variants.map((variant) => {
      const ret = [];
      variant.labels.map(() => {
        const labelForm = new FormControl({id: new FormControl(''), label: new FormControl('')});
        ret.push( labelForm );
      })
      this.addVariant(ret);
    })
  }

  setProduct(): void {
    this.route.params.subscribe((params) => {
      const uuid = params['puuid'];
      this.store.select('product').subscribe((data: any) => {
        const elem = data.products.find(elem => elem.uuid === uuid);
        if(elem) {
          this.product = elem;
        }
      })
    })
  }

  setVariants(): void {
    console.log(this.variantForm);
    this.route.data.subscribe((data: any) => {
      console.log(data.variants.variants);
      const variants = data.variants.variants;
      this.addVariants(variants);
      this.variantForm.setValue(data.variants.variants);
      console.log(this.variantForm.value);
    })
  }

  onSubmit(): void {
    console.log('submit');
    this.http.patch('/api/product/' + this.product.uuid + '/variant', { variants : this.variantForm.value})
      .subscribe((data:any) => {
      console.log(data);
      this.variantForm.patchValue(data.variants);
    })
  }

}
