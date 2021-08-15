import {
  AfterViewInit,
  ChangeDetectorRef,
  Component, forwardRef,
  Input,
  OnChanges,
  OnInit,
  SimpleChange,
  SimpleChanges
} from '@angular/core';
import {Variant} from "@shared";
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
  selector: 'app-remove-element',
  templateUrl: './remove-element.component.html',
  styleUrls: ['./remove-element.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => RemoveElementComponent),
      multi: true
    }
  ]
})
export class RemoveElementComponent implements OnInit, OnChanges, ControlValueAccessor {

  @Input() variants: Variant[];

  availableVariants = [];

  names = [];

  sets = [];

  propagateChange = (_: any) => {};


  constructor(private cdref: ChangeDetectorRef) { }

  ngOnInit(): void {
    this.init();
  }

  writeValue(value: any) {
    if (value) {
      this.setValue(value);
    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }



  ngOnChanges(changes: SimpleChanges) {
    this.init();
    this.cdref.detectChanges();
  }

  init(): void {
    this.availableVariants = [{id: null, name: 'Choisir un valeur'}, ...this.variants];

  }

  onChange(nameId): void {
    const index = parseInt(nameId);
    if(index && this.availableVariants.length > 1 ) {
      let i = this.availableVariants.findIndex(elem => elem.id === index);
      let  name = this.availableVariants.splice(i, 1 )[0];
      if(name) {
        name = {...name, labels: [{id: null, label: 'choisir'}, ...name.labels]};
        this.names.push(name);
      }
    }
  }

  onChangeVariant(labelId, nameId, index) {
    if (this.names[index]) {
      this.sets.push({
        index,
        variantNameId: nameId,
        variantNameValue: this.names[index].name,
        variantLabelId: parseFloat(labelId),
        variantLabelValue: this.names[index].labels.find(elem => elem.id === parseFloat(labelId))?.label
      });
      this.propagateChange(this.getValue());
    }
  }

  removeVariant(index) {
    let i = this.sets.findIndex(elem => elem.index === index);
    this.sets.splice(i, 1);
    let name = this.names.splice( index, 1)[0];
    name.labels.splice(0, 1);
    this.availableVariants.push(name);
    this.propagateChange(this.getValue());
  }

  displayText(nameId) {
   return this.sets.find(elem => elem.variantNameId === nameId);
  }

  getValue(): string {
    return this.sets.map( elem => elem.variantNameId + '_'+ elem.variantLabelId).join('#');
  }

  setValue( value): void {
    this.init();
    this.sets = [];
    const values = value.split('#').map(elem => elem.split('_').map(elem => parseInt(elem)));
    values.forEach((elem, index) => {
      this.onChange(elem[0]);
      this.onChangeVariant(elem[1], elem[0], index);
    })
  }
}
