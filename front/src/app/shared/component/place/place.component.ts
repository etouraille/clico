import {Component, forwardRef, OnInit} from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR} from '@angular/forms';

import * as $ from 'jquery';
@Component({
  selector: 'app-place',
  templateUrl: './place.component.html',
  styleUrls: ['./place.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => PlaceComponent),
      multi: true
    }
  ]
})
export class PlaceComponent implements OnInit, ControlValueAccessor {

  address: any;
  propagateChange = (_: any) => {};

  constructor() { }

  ngOnInit() {
  }

  writeValue(value: any) {
    if (value) {
      this.address = value;
    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }

  handleAddressChange(event: any) : void {
    const obj = {address: event.formatted_address,
      lat : event.geometry.location.lat(),
      lng :event.geometry.location.lng()
    };
    this.propagateChange(obj)
  }
}
