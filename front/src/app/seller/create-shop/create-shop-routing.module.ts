import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CreateShopComponent } from "./create-shop.component";


const createShopRoutes: Routes = [
  {
    path: '',
    component: CreateShopComponent,
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(createShopRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class CreateShopRoutingModule { }
