import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {HttpClient} from "@angular/common/http";
import {Product} from "@shared";

@Component({
  selector: 'app-product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent implements OnInit {

  product : Product;
  productVariant: any;

  constructor(
    private route: ActivatedRoute,
    private http: HttpClient,
    ) { }

  ngOnInit(): void {
    this.setProduct();
  }

  setProduct(): void {
    this.route.params.subscribe(elem => {
      let uuid = elem.uuid;
        this.http.get('/product/' + uuid).subscribe( (product: any) => {
          this.product = product;
        })
    })
  }
}
