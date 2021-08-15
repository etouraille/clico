import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {CreateShopComponent} from "../../seller/create-shop/create-shop.component";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {GooglePlaceModule} from "ngx-google-places-autocomplete";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {CreateSelectComponent, HeaderComponent, ImgComponent, PlaceComponent} from "../component";
import {FileUploadComponent} from "../component/file-upload/file-upload.component";
import {NgbDropdownModule} from "@ng-bootstrap/ng-bootstrap";
import {MatCardModule} from "@angular/material/card";
import {FlexLayoutModule} from "@angular/flex-layout";
import {MatListModule} from "@angular/material/list";
import {MatTableModule} from "@angular/material/table";
import {MatPaginatorModule} from "@angular/material/paginator";
import {MatSortModule} from "@angular/material/sort";
import {MatProgressSpinnerModule} from "@angular/material/progress-spinner";
import {MatInputModule} from "@angular/material/input";
import {MatTabsModule} from "@angular/material/tabs";
import {PicturesComponent} from "../component/pictures/pictures.component";
import {RemoveElementComponent} from "../component/remove-element/remove-element.component";
import {MatRadioModule} from "@angular/material/radio";
import {FrontVariantComponent} from "../component/front/front-variant/front-variant.component";
import {DragDropModule} from "@angular/cdk/drag-drop";
import {VariantAutocompleteComponent} from "../component/variant-autocomplete/variant-autocomplete.component";
import {MatAutocompleteModule} from "@angular/material/autocomplete";



@NgModule({
  declarations: [
    CreateShopComponent,
    PlaceComponent,
    FileUploadComponent,
    HeaderComponent,
    ImgComponent,
    CreateSelectComponent,
    PicturesComponent,
    RemoveElementComponent,
    FrontVariantComponent,
    VariantAutocompleteComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    GooglePlaceModule,
    MatProgressBarModule,
    MatIconModule,
    MatButtonModule,
    FormsModule,
    NgbDropdownModule,
    MatCardModule,
    FlexLayoutModule,
    MatListModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatProgressSpinnerModule,
    MatInputModule,
    MatTabsModule,
    MatRadioModule,
    DragDropModule,
    MatAutocompleteModule,

  ],
  exports: [
    CreateShopComponent,
    ReactiveFormsModule,
    FormsModule,
    FileUploadComponent,
    MatIconModule,
    HeaderComponent,
    NgbDropdownModule,
    MatCardModule,
    FlexLayoutModule,
    ImgComponent,
    MatListModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatProgressSpinnerModule,
    MatInputModule,
    MatTabsModule,
    MatButtonModule,
    CreateSelectComponent,
    PicturesComponent,
    RemoveElementComponent,
    MatRadioModule,
    FrontVariantComponent,
    DragDropModule,
    VariantAutocompleteComponent,
    MatAutocompleteModule,
  ]
})
export class SharedModule { }
