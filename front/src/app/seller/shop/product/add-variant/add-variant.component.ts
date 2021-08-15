import { Component, OnInit } from '@angular/core';
import {FormArray, FormBuilder, FormControl} from "@angular/forms";

@Component({
  selector: 'app-add-variant',
  templateUrl: './add-variant.component.html',
  styleUrls: ['./add-variant.component.css']
})
export class AddVariantComponent implements OnInit {

  addVariantForm = this.fb.group({variants: new FormArray([])});

  constructor(private fb: FormBuilder) { }

  ngOnInit(): void {
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

}
