import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {VariantProduct} from "@shared";
import {FormArray, FormBuilder, FormControl, Validators} from "@angular/forms";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-edit-variant-product',
  templateUrl: './edit-variant-product.component.html',
  styleUrls: ['./edit-variant-product.component.css']
})
export class EditVariantProductComponent implements OnInit {

  variantProduct: VariantProduct;

  formVariantProduct = this.fb.group({
    id: ['', Validators.required],
    label: ['', Validators.required],
    price: [''],
    pictures: new FormControl([])
  })

  constructor(
    private route: ActivatedRoute,
    private fb: FormBuilder,
    private http: HttpClient,
    ) {}

  ngOnInit(): void {
    this.setVariantProduct();
    this.formVariantProduct.patchValue(this.variantProduct);
  }

  private setVariantProduct(): void {
    this.route.data.subscribe((data: any) => {
      this.variantProduct = data.variantProduct;
    })
  }

  onSubmit(): void {
    console.log(this.formVariantProduct.value);
    this.http.patch('/api/variant-product', this.formVariantProduct.value).subscribe((variantProduct: VariantProduct) => {
      this.formVariantProduct.patchValue(variantProduct);
    })
  }
}
