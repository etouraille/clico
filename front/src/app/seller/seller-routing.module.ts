import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SellerComponent } from "./seller.component";
import {HomeComponent} from "./home/home.component";
import {CurrentShopResolverService, ProductsResolverService, ShopResolverService} from "@shared";
import {ProductResolverService} from "../shared/resolver/product-resolver.service";


const sellerRoutes: Routes = [
  {
    path: '',
    component: SellerComponent,
    resolve: {
      shop: CurrentShopResolverService,
    },
    children: [
      {
        path: '',
        component: HomeComponent,
      },
      {
        path: 'je-creee-ma-boutique',
        loadChildren: () => import('./create-shop/create-shop.module').then(m => m.CreateShopModule),
      },
      {
        path: 'ma-boutique/:uuid',
        loadChildren:() => import('./shop/shop.module').then(m => m.ShopModule ),
        resolve: {
          product: ProductsResolverService,
          shop: ShopResolverService
        }
      }
    ]
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(sellerRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class SellerRoutingModule { }
