import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CreateShopRoutingModule } from "./create-shop-routing.module";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import { CreateShopComponent } from "./create-shop.component";
import {SharedModule} from "@shared";
import {GooglePlaceModule} from "ngx-google-places-autocomplete";
import {FileUploadComponent} from "../../shared/component/file-upload/file-upload.component";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";



@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    CreateShopRoutingModule,
    SharedModule,

  ]
})
export class CreateShopModule { }
