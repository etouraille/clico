import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SellerComponent } from './seller.component';
import { SellerRoutingModule } from "./seller-routing.module";
import { CreateShopComponent } from './create-shop/create-shop.component';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import { ShopComponent } from './shop/shop.component';
import {PlaceComponent, SharedModule, shopReducer} from "@shared";
import {FileUploadComponent} from "../shared/component/file-upload/file-upload.component";
import {GooglePlaceModule} from "ngx-google-places-autocomplete";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {HomeComponent} from "./home/home.component";
import { StoreModule } from '@ngrx/store';



@NgModule({
  declarations: [
    SellerComponent,
    ShopComponent,
    HomeComponent,
  ],
  imports: [
    CommonModule,
    SellerRoutingModule,
    SharedModule,

  ]
})
export class SellerModule { }
