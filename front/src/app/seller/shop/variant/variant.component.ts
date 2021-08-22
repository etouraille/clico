import {ChangeDetectorRef, Component, Input, OnInit, Output} from '@angular/core';
import {FormArray, FormBuilder, FormControl, FormGroup} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";
import {Store} from "@ngrx/store";
import {Product, UtilsService, Variant} from "@shared";
import {HttpClient} from "@angular/common/http";
import { EventEmitter } from "@angular/core";
import {CdkDragDrop, moveItemInArray, transferArrayItem} from "@angular/cdk/drag-drop";

@Component({
  selector: 'app-variant',
  templateUrl: './variant.component.html',
  styleUrls: ['./variant.component.css']
})
export class VariantComponent implements OnInit {

  @Output() variantsChange = new EventEmitter<Variant[]>();
  @Input() variants = [];
  variantForm = this.fb.group({  variants:  new FormArray([])});
  product: Product;


  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private store: Store<{ product: Product}>,
    private http: HttpClient,
    private utilsService: UtilsService,
  ) { }

  ngOnInit(): void {
    this.setProduct();
    this.setVariants();
  }

  get variantArray() {
    return this.variantForm.get('variants') as FormArray;
  }

  addVariant(ret?: any): void {
    let index = this.variantArray.length;
    if(!ret) {
      this.variantArray.push(new FormControl({id : '' , type: '', name : '', rank: index , labels: []}));
    } else {
      this.variantArray.push(new FormControl({id : '', type: '', name : '', rank: index, labels: ret}));
    }
  }

  removeVariant(index: number) {
    this.variantArray.removeAt(index);
    let values = this.variantForm.value.variants;
    values = this.utilsService.reorderRemove(values, index);
    this.variantForm.setValue({ variants: values});

    // this.variantForm.controls.splice(index,1)
    // this.variantForm.value.splice(index, 1);
  }

  addVariants(variants: any ) : void {
    variants.map((variant) => {
      const ret = [];
      variant.labels.map(() => {
        const labelForm = new FormControl({
          id: new FormControl(''),
          label: new FormControl(''),
          rank: new FormControl(''),
        });
        ret.push( labelForm );
      })
      this.addVariant(ret);
    })
  }

  setProduct(): void {
    this.route.params.subscribe((params) => {
      const uuid = params['puuid'];
      this.store.select('product').subscribe((data: any) => {
        const elem = data.products.find(elem => elem.uuid === uuid);
        if(elem) {
          this.product = elem;
        }
      })
    })
  }

  setVariants(): void {
    this.route.data.subscribe((data: any) => {
      this.addVariants(data.variants.variants);
      this.variantForm.setValue({ variants: data.variants.variants});
      this.variantsChange.emit(data.variants.variants);
      // this.cdref.detectChanges();
    })
  }

  onSubmit(): void {
    this.http.patch('/api/variant', { variants : this.variantForm.value.variants})
      .subscribe((data:any) => {
      this.variantForm.patchValue(data.variants);
      this.variantsChange.emit(data.variants);
    })
  }

  drop(event: CdkDragDrop<string[]>) {
    //if (event.previousContainer === event.container) {
      moveItemInArray(event.container.data,
        event.previousIndex,
        event.currentIndex);

      let values = this.variantArray.controls.map(elem => elem.value);
      this.utilsService.reorderMove(values, event.previousIndex, event.currentIndex);
      this.variantForm.setValue({variants: values});

      //console.log(this.variantArray.controls);

    /*} else {
      transferArrayItem(event.previousContainer.data,
        event.container.data,
        event.previousIndex, event.currentIndex);
    }*/
  }

}
