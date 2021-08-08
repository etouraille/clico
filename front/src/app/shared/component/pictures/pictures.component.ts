import {AfterViewInit, Component, forwardRef, OnInit} from '@angular/core';
import {ControlValueAccessor, FormArray, FormBuilder, FormControl, FormGroup, NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
  selector: 'app-pictures',
  templateUrl: './pictures.component.html',
  styleUrls: ['./pictures.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => PicturesComponent),
      multi: true
    }
  ]
})
export class PicturesComponent implements OnInit, ControlValueAccessor, AfterViewInit{


  pictureForm = this.fb.group({
    pictures: new FormArray([])
  });

  propagateChange = (_: any) => {};

  constructor(private fb: FormBuilder) { }

  ngOnInit(): void {

  }

  ngAfterViewInit() {
    this.pictureForm.valueChanges.subscribe((values)=> {
      this.propagateChange(values.pictures);
    })
  }

  writeValue(value: any) {
    console.log( value);
    if (value) {
      //this.reset();
      //this.pictureForm.patchValue({ pictures: value})
      this.pictures.clear();

      value.forEach(elem => {
        this.addPicture(elem);
      })


    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }

  get pictures() {
    return this.pictureForm.get('pictures') as FormArray;
  }

  set pictures(value: any) {
    this.pictureForm.get('pictures').setValue(value);
  }

  addPicture(value?: any): void {
    this.pictures.push(new FormControl(value));
  }

  removePicture(index): void {
    this.pictures.removeAt(index)
  }


  reset(): void {
    console.log(this.pictures.controls.length);
    for( var i = 0;i < this.pictures.controls.length; i++){
      this.removePicture(i);
    }
  }

}
