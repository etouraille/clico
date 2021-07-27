import {Component, Input, OnInit} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from "@angular/forms";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";
import {Shop} from "@shared";

@Component({
  selector: 'app-create-shop',
  templateUrl: './create-shop.component.html',
  styleUrls: ['./create-shop.component.css']
})
export class CreateShopComponent implements OnInit {

  src : string;
  products = [
    'Produits agricoles',
    'Materiel Informatique',
    'Livres',
  ];

  @Input() shop: Shop;

  createShopForm = this.fb.group({
    uuid: [''],
    name: ['', Validators.required],
    type: ['', Validators.required],
    address: this.fb.group({
      address: ['', Validators.required],
      lat: ['', Validators.required],
      lng: ['', Validators.required],
    }),
    email: ['', Validators.email],
    phone: [''],
    mobile: ['']
  })

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {}

  ngOnInit(): void {
    if (this.shop) {
      console.log(this.shop);
      this.createShopForm.patchValue(this.shop);
    }
  }

  uploadComplete(event: any): void {
    this.src = 'http://localhost:8081/file/' + event.body.file;
  }

  onSubmit(): void {
    if (this.createShopForm.value.uuid) {
      this.http.patch('/api/shop', this.createShopForm.value).subscribe((data) => {

      });
    } else {
      this.http.post('/api/create-shop', this.createShopForm.value).subscribe((data: any) => {
        console.log(data);
        this.router.navigate(['/je-vends/ma-boutique/' + data.uuid]);
      })
    }
  }

  disabled() : boolean {
    return !this.createShopForm.valid;
  }

}
