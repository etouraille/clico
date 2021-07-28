import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ShopComponent } from "./shop.component";
import {HomeComponent} from "./home/home.component";
import {ProductsComponent} from "./products/products.component";
import {CreateProductComponent} from "./create-product/create-product.component";
import {ProductComponent} from "./product/product.component";
import { ProductResolverService } from "../../shared/resolver/product-resolver.service";


const shopRoutes: Routes = [
  {
    path: '',
    component: ShopComponent,
    children: [
      {
        path :  '',
        component: HomeComponent,
      },
      {
        path :  'produits',
        component: ProductsComponent,
      },
      {
        path :  'produit/:puuid',
        component: ProductComponent,
      },
      {
        path :  'je-creee-un-produit',
        component: CreateProductComponent,
      },
    ]
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(shopRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class ShopRoutingModule { }
