import {Component, Input, OnInit} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from "@angular/forms";
import {HttpClient} from "@angular/common/http";
import {ActivatedRoute, ActivatedRouteSnapshot, Router} from "@angular/router";
import {setShop, Shop} from "@shared";
import {Store} from "@ngrx/store";

@Component({
  selector: 'app-create-shop',
  templateUrl: './create-shop.component.html',
  styleUrls: ['./create-shop.component.css']
})
export class CreateShopComponent implements OnInit {

  src : string;
  product = [
    'Produits agricoles',
    'Materiel Informatique',
    'Livres',
  ];

  @Input() shop: Shop;

  createShopForm = this.fb.group({
    uuid: [''],
    name: ['', Validators.required],
    type: ['', Validators.required],
    address: new FormControl({
      address: new FormControl('', Validators.required),
      lat: new FormControl('', Validators.required),
      lng: new FormControl('', Validators.required),
    }),
    email: ['', Validators.email],
    phone: [''],
    mobile: ['']
  })

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router,
    private store: Store<{ shop: Shop }>
  ) {}

  ngOnInit(): void {
    this.store.select('shop').subscribe((data: any ) => {
      let shop = data.shop;
      if (shop) {
        console.log(shop);
        this.createShopForm.patchValue(shop);
      }
    })

  }

  uploadComplete(event: any): void {
    this.src = 'http://localhost:8081/file/' + event.body.file;
  }

  onSubmit(): void {
    if (this.createShopForm.value.uuid) {
      this.http.patch('/api/shop', this.createShopForm.value).subscribe((data:Shop) => {
        this.store.dispatch(setShop({shop: data}));
      });
    } else {
      this.http.post('/api/create-shop', this.createShopForm.value).subscribe((data: any) => {
        this.store.dispatch(setShop({shop: data}));
        this.router.navigate(['/je-vends/ma-boutique/' + data.uuid]);
      })
    }
  }

  disabled() : boolean {
    return !this.createShopForm.valid;
  }

}
