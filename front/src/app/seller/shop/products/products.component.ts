import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {deleteProduct, Product, Shop} from "@shared";
import {Store} from "@ngrx/store";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit {

  shop: Shop;
  products: Product[] = [];

  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private store: Store<{shop: Shop, product: Product[]}>,
    private http: HttpClient,
  )
  { }

  ngOnInit(): void {
    this.setShopAndproduct();
  }

  createProduct() : void {
    this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/je-creee-un-produit']);
  }

  private setShopAndproduct(): void {
    this.store.select('shop').subscribe((data: any)=> {
      this.shop = data.shop;
    })
    this.store.select('product').subscribe((data: any) => {
      this.products = data.products;
    });
  }

  navigate(uuid: string ): void {
    this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/produit/' + uuid])
  }

  onDeleteProduct(uuid: string): void {
    this.http.delete('/api/product/' + uuid).subscribe(() => {
      this.store.dispatch(deleteProduct({uuid}));
    })
  }
}
