import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SellerComponent } from "./seller.component";
import {HomeComponent} from "./home/home.component";
import {ShopResolverService} from "@shared";


const sellerRoutes: Routes = [
  {
    path: '',
    component: SellerComponent,
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
          shop: ShopResolverService,
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
