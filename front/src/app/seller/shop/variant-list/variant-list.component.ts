import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";
import {Shop} from "@shared";
import {Store} from "@ngrx/store";

@Component({
  selector: 'app-variant-list',
  templateUrl: './variant-list.component.html',
  styleUrls: ['./variant-list.component.css']
})
export class VariantListComponent implements OnInit {

  variants : [];

  displayedColumns : any = ['name', 'labels', 'action', 'removed'];

  shop: Shop;

  constructor(
    private http: HttpClient,
    private router: Router,
    private store: Store<{shop: Shop}>
  ) { }

  ngOnInit(): void {

    // TODO : mettre en place la pagination.
    this.http.get('/api/variant-product').subscribe((variants: any) => {
      this.variants = variants;
    })

    this.setShop();
  }

  private setShop(): void {
    this.store.select('shop').subscribe((data: any)=> {
      this.shop = data.shop;
    })
  }

  edit(id) {
    this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/variant/' + id]);
  }

  toggleRemove( removed: boolean , variantMapping: string, productUuid: string) {
    this.http.post('/api/product/' + productUuid + '/variant-removed', {
      add : removed, // si il removed il faut l'ajouter
      variantMapping,
    }).subscribe();
  }

}
