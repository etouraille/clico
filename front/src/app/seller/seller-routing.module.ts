import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SellerComponent } from "./seller.component";
import { AuthGuard } from "@shared";


const sellerRoutes: Routes = [
  {
    path: '',
    component: SellerComponent,
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
