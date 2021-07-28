import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {CreateShopComponent} from "../../seller/create-shop/create-shop.component";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {GooglePlaceModule} from "ngx-google-places-autocomplete";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {PlaceComponent} from "../component";
import {FileUploadComponent} from "../component/file-upload/file-upload.component";



@NgModule({
  declarations: [CreateShopComponent, PlaceComponent, FileUploadComponent],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    GooglePlaceModule,
    MatProgressBarModule,
    MatIconModule,
    MatButtonModule,
    FormsModule,

  ],
  exports: [
    CreateShopComponent,
    ReactiveFormsModule,
    FileUploadComponent,
    MatIconModule,
  ]
})
export class SharedModule { }
