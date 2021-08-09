import {ChangeDetectorRef, Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {Variant} from "@shared";
import {FormArray, FormBuilder, FormControl} from "@angular/forms";

@Component({
  selector: 'app-remove-variant',
  templateUrl: './remove-variant.component.html',
  styleUrls: ['./remove-variant.component.css']
})
export class RemoveVariantComponent implements OnInit, OnChanges {

  @Input() variants: Variant[];

  form = this.fb.group({
    removed : new FormArray([])
  })


  constructor(
    private fb: FormBuilder,
    private cdref: ChangeDetectorRef
  ) { }

  ngOnInit(): void {
    console.log(this.variants);
  }

  ngOnChanges(changes: SimpleChanges) {
    console.log(this.variants);
    // this.cdref.detectChanges();
  }

  addRemove(): void {
    this.removed.push(this.fb.control(''));
  }

  get removed() {
    return this.form.get('removed') as FormArray;
  }

}
