import {ChangeDetectorRef, Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {Product, Variant} from "@shared";
import {FormArray, FormBuilder, FormControl} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";
import {Store} from "@ngrx/store";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-remove-variant',
  templateUrl: './remove-variant.component.html',
  styleUrls: ['./remove-variant.component.css']
})
export class RemoveVariantComponent implements OnInit, OnChanges {

  @Input() variants: Variant[];

  productUuid: string;

  form = this.fb.group({
    removing : new FormArray([])
  })


  constructor(
    private fb: FormBuilder,
    private cdref: ChangeDetectorRef,
    private route: ActivatedRoute,
    private store: Store<{product: Product}>,
    private http: HttpClient,
  ) { }

  ngOnInit(): void {

    this.setProduct();

  }

  ngOnChanges(changes: SimpleChanges) {
    // this.cdref.detectChanges();
  }

  addRemove(value?: string): void {
    this.removed.push(this.fb.control(value ? value: ''));
  }

  get removed() {
    return this.form.get('removing') as FormArray;
  }

  removeAt(index): void  {
    this.removed.removeAt(index);
  }

  onSubmit() {
    this.http.patch('/api/product/' +this.productUuid +'/variant-removed', this.form.value.removing)
      .subscribe(data => console.log(data));

  }

  setProduct(): void {
    this.route.params.subscribe((params) => {
      this.productUuid = params['puuid'];
      this.http.get('/api/product/' + this.productUuid + '/variant-removed').subscribe((data: any) => {
        data.forEach( variantMapping => {
          this.addRemove(variantMapping);
        })
      })
    })
  }
}
