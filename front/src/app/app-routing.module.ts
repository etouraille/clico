import { NgModule } from '@angular/core';
import { PreloadAllModules, Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import {PageNotFoundComponent} from "./page-not-found/page-not-found.component";
import {AuthGuard} from "@shared";


const appRoutes: Routes = [
  { path: '',
    component: AppComponent,
    children: [
      {
        path: '',
        pathMatch: 'full',
        loadChildren: () => import('./home/home.module').then(m => m.HomeModule),
      },
      {
        path: 'je-vends',
        loadChildren: () => import('./seller/seller.module').then(m => m.SellerModule),
        canLoad: [AuthGuard],
        canActivate: [AuthGuard],
      },
      {
        path: 'connexion',
        loadChildren: () => import('./login/login.module').then(m => m.LoginModule),
      },
    ]
  },
  { path: '**', component: PageNotFoundComponent }
];

@NgModule({
  imports: [
    RouterModule.forRoot(
      appRoutes,
      {
        // enableTracing: true, // <-- debugging purposes only
        preloadingStrategy: PreloadAllModules,
        paramsInheritanceStrategy: 'always', // Access both parent and child route params using activeRoute.params
      })
  ],
  exports: [
    RouterModule
  ]
})
export class AppRoutingModule { }
