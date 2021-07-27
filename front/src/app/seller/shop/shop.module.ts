import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShopRoutingModule } from "./shop-routing.module";
import { ShopComponent } from "./shop.component";
import {CreateShopComponent} from "../create-shop/create-shop.component";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {GooglePlaceModule} from "ngx-google-places-autocomplete";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";



@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    ShopRoutingModule,
    ReactiveFormsModule,
    GooglePlaceModule,
    MatProgressBarModule,
    MatIconModule,
    MatButtonModule,
    FormsModule,
  ]
})
export class ShopModule { }
