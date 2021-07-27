import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ShopComponent } from "./shop.component";


const shopRoutes: Routes = [
  {
    path: '',
    component: ShopComponent,
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
