import {AfterViewInit, Component, forwardRef, Input, OnInit, Output} from '@angular/core';
import {ControlValueAccessor, FormArray, FormBuilder, FormControl, FormGroup, NG_VALUE_ACCESSOR} from "@angular/forms";
import { EventEmitter } from "@angular/core";
@Component({
  selector: 'app-detail-variant',
  templateUrl: './detail-variant.component.html',
  styleUrls: ['./detail-variant.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => DetailVariantComponent),
      multi: true
    }
  ]
})
export class DetailVariantComponent implements OnInit, ControlValueAccessor, AfterViewInit {

  variantTypes = ['radio', 'select', 'color', 'picture'];

  constructor(private fb: FormBuilder) { }

  @Output() onRemove = new EventEmitter<null>();
  @Input() rank: number;

  variant: FormGroup;

  propagateChange = (_: any) => {};

  ngOnInit(): void {
    this.variant = new FormGroup({
      id: new FormControl(''),
      type: new FormControl(''),
      name: new FormControl(''),
      rank : new FormControl(''),
      labels: new FormControl([])
    });

  }

  ngAfterViewInit() {
    if(this.variant) {
      this.variant.valueChanges.subscribe(values => {
        this.propagateChange(values);
      })
    }
  }


  writeValue(value: any) {
    if (value) {
      if(value.labels && Array.isArray(value.labels)) {
        let repeat = value.labels.length - this.variant.get('labels').value.length
        for(let i = 0;i< repeat;i++) {
          this.variant.get('labels').value.push(new FormGroup({id: new FormControl(''), label: new FormControl('')}));
        }
      } else {
        this.variant.get('labels').value.push([]);
      }
      this.variant.setValue(value);
    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }


  remove(): void {
    this.onRemove.emit(null);
  }

}
