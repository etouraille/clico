import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from "./home.component";
import {ProductComponent} from "./product/product.component";


const homeRoutes: Routes = [
  {
    path: '',
    component: HomeComponent,
  },{
    path: 'produit/:uuid',
    component: ProductComponent,
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(homeRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class HomeRoutingModule { }
