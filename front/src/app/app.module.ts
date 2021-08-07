import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {AddTokenInterceptor, CreateSelectComponent, productReducer, SharedModule, shopReducer} from "@shared";
import { LoginComponent } from './login/login.component';
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FileUploadComponent } from './shared/component/file-upload/file-upload.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {StoreModule} from "@ngrx/store";
import { SettingComponent } from './setting/setting.component';
import { ChangePasswordComponent } from './setting/change-password/change-password.component';
import { ImgComponent } from './shared/component/img/img.component';

@NgModule({
  declarations: [
    AppComponent,
    PageNotFoundComponent,
    LoginComponent,
    SettingComponent,
    ChangePasswordComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
    FormsModule,
    NgbModule,
    BrowserAnimationsModule,
    StoreModule.forRoot({
      shop: shopReducer,
      product: productReducer
    }, {
      runtimeChecks: {
        strictStateImmutability: false,
        strictActionImmutability: false,
      },
    }),
    SharedModule

  ],
  providers: [
    {provide: HTTP_INTERCEPTORS, useClass: AddTokenInterceptor, multi: true},
  ],
  exports: [
    CreateSelectComponent


  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
