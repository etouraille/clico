import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SellerComponent } from './seller.component';
import {SellerRoutingModule} from "./seller-routing.module";



@NgModule({
  declarations: [SellerComponent],
  imports: [
    CommonModule,
    SellerRoutingModule,
  ]
})
export class SellerModule { }
