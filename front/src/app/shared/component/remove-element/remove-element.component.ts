import {ChangeDetectorRef, Component, Input, OnChanges, OnInit, SimpleChange, SimpleChanges} from '@angular/core';
import {Variant} from "@shared";

@Component({
  selector: 'app-remove-element',
  templateUrl: './remove-element.component.html',
  styleUrls: ['./remove-element.component.css']
})
export class RemoveElementComponent implements OnInit, OnChanges {

  @Input() variants: Variant[];

  availableVariants = [];

  names = [];

  constructor(private cdref: ChangeDetectorRef) { }

  ngOnInit(): void {
    this.init();
  }

  addVariant(): void {

  }

  ngOnChanges(changes: SimpleChanges) {
    this.init();
    this.cdref.detectChanges();
  }

  init(): void {
    this.availableVariants = [{id: null, name: 'Choisir un valeur'}, ...this.variants];
    console.log(this.availableVariants);
  }

  onChange(event): void {
    console.log(this.availableVariants);
    const index = parseInt(event.target.value);
    if(index) {
      let i = this.availableVariants.findIndex(elem => elem.id === index);
      this.names  = [ ...this.names, ...this.availableVariants.splice(i, 1 )];
    }
  }
}
