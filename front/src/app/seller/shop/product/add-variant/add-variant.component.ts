import { Component, OnInit } from '@angular/core';
import {FormArray, FormBuilder, FormControl} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-add-variant',
  templateUrl: './add-variant.component.html',
  styleUrls: ['./add-variant.component.css']
})
export class AddVariantComponent implements OnInit {

  addVariantForm = this.fb.group({variants: new FormArray([])});

  productUuid : string;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private http: HttpClient,
    ) { }

  ngOnInit(): void {
    this.setProduct();
    this.initData();
  }

  get variants() {
    return this.addVariantForm.get('variants') as FormArray;
  }

  remove(i) {
    this.variants.removeAt(i);
  }

  add(): void {
    this.variants.push(new FormControl(''));
  }

  onSubmit() {
    this
      .http
      .patch('/api/product/' + this.productUuid + '/variant', this.addVariantForm.value)
      .subscribe(data => console.log(data));
  }

  setProduct(): void {
    this.route.params.subscribe((params) => {
      const uuid = params['puuid'];
      this.productUuid = uuid;
    })
  }

  initData(): void {
    this.http.get('/api/product/' + this.productUuid + '/variant').subscribe(
      (variants: any) => {
        for( let i = 0; i< variants.variants.length;i ++) {
          this.add();
        }
        this.addVariantForm.setValue(variants);
      }
    )
  }
}
