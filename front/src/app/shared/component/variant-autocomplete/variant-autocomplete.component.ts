import {ChangeDetectorRef, Component, forwardRef, OnChanges, OnInit} from '@angular/core';
import {ControlValueAccessor, FormControl, NG_VALUE_ACCESSOR} from "@angular/forms";
import {Observable} from "rxjs";
import {debounceTime, distinctUntilChanged, map, startWith, switchMap} from "rxjs/operators";
import {HttpClient} from "@angular/common/http";
import {MatAutocompleteSelectedEvent} from "@angular/material/autocomplete";

@Component({
  selector: 'app-variant-autocomplete',
  templateUrl: './variant-autocomplete.component.html',
  styleUrls: ['./variant-autocomplete.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => VariantAutocompleteComponent),
      multi: true
    }
  ]
})
export class VariantAutocompleteComponent implements OnInit, ControlValueAccessor {

  myControl = new FormControl();
  filteredOptions: Observable<any>;
  labels: any;

  propagateChange = (_: any) => {};

  writeValue(value: any) {
    console.log(value);
    if (value) {
      this.myControl.setValue(value);
      this.labels = value.labels;
    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }

  ngOnInit() {
    this.filteredOptions = this.myControl.valueChanges
      .pipe(
        debounceTime(150),
        distinctUntilChanged(),
        startWith(''),
        switchMap(value => this.http.post<any>('/api/variant-name/query', { query: value})),
        map(data => data.variants)
      );
  }

  onSelected(option: MatAutocompleteSelectedEvent) {
    // set value.
    this.propagateChange(option.option.value);
    this.labels = option.option.value.labels;
  }

  displayFn(value) {
    return value ? value?.name: '';
  }

  constructor(private http: HttpClient) { }
}
