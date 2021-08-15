import {Component, Input, OnChanges, OnInit, Output, SimpleChanges, EventEmitter} from '@angular/core';
import {Product} from "@shared";

@Component({
  selector: 'app-front-variant',
  templateUrl: './front-variant.component.html',
  styleUrls: ['./front-variant.component.css']
})
export class FrontVariantComponent implements OnInit, OnChanges {

  @Input() product: any;
  variants : any[] = [];
  values: any = {};
  @Output() change = new EventEmitter<any>();

  constructor() {}

  ngOnInit(): void {
    // this.init();
  }

  ngOnChanges(changes: SimpleChanges) {
    this.init();
  }

  onChange(event, name): void {
    this.values[name] = event.value;
    this.change.emit(this.getCurrentVariant());
  }


  init(): void {
    this.product.variantProducts.forEach( (vp: any) => {
      vp.labels.forEach((label:any) => {
        let index = this.variants.findIndex((elem: any) => elem.name === label.name );
        if( index < 0 ) {
          this.variants.push({ name : label.name, labels : [{ id : label.id, label: label.label }]})
        } else {
           let j = this.variants[index].labels.findIndex((some: any) => some.id === label.id);
           if(j < 0) {
             this.variants[index].labels.push({id: label.id, label: label.label});
           }
        }
      })
    })
    this.variants.forEach(elem => {
      this.values[elem.name] = this.product.variantProducts[0].labels.find(some => some.name === elem.name).id;
    })
  }

  getCurrentVariant(): any {
    let remainings = this.product.variantProducts;
    Object.keys(this.values).forEach((key) => {
      remainings = remainings.filter(elem => {
        return elem.labels.findIndex(some => some.id === this.values[key] && some.name === key) >= 0;
      })
    })
    console.log( remainings);
    return remainings[0];
  }
}
