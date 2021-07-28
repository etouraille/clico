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
import { HomeComponent } from './home/home.component';
import {SharedModule} from "@shared";
import { ProductsComponent } from './products/products.component';
import { CreateProductComponent } from './create-product/create-product.component';
import { ProductComponent } from './product/product.component';



@NgModule({
  declarations: [HomeComponent, ProductsComponent, CreateProductComponent, ProductComponent],
  imports: [
    CommonModule,
    ShopRoutingModule,
    SharedModule,
  ]
})
export class ShopModule { }
