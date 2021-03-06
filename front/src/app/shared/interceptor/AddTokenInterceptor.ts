import { Injectable } from '@angular/core';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse } from '@angular/common/http';
import { Observable, of, throwError } from 'rxjs';
import { AuthService } from "../service/auth.service";
import { Router } from "@angular/router";
import {catchError, exhaustMap, mergeMap, take} from "rxjs/operators";
import { environment } from "../../../environments/environment";
import {Store} from "@ngrx/store";
import {Shop} from "../model";

@Injectable()
export class AddTokenInterceptor implements HttpInterceptor {

  constructor(private authService: AuthService, private router: Router, private store: Store<{shop: Shop}>) {

  }

  private handleAuthError(err: HttpErrorResponse): Observable<any> {
    if (err.status === 401 || err.status === 403) {
      // manage the case of different login type : user or seller.
      this.router.navigate([`/connexion`]);
      return of(err.message);
    }
    return throwError(err);
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    const userToken = this.authService.getUserToken();

    /*
    const modifiedReq = req.clone({
      headers: req.headers.set('Authorization', `Bearer ${userToken}`).set('Shop', `Shop ~00000`),
      url: req.url.match(/https?/) ? req.url : environment.api + req.url,
    });

    return next
      .handle(modifiedReq)
      .pipe(catchError(x=> this.handleAuthError(x)));
    */

    return this.getShop().pipe(
      take(1),
      exhaustMap((data: any) => {
        const modifiedReq = req.clone({
          headers: req.headers.set('Authorization', `Bearer ${userToken}`).set('Shop', `Shop ${data.shop.uuid}`),
          url: req.url.match(/https?/) ? req.url : environment.api + req.url,
        });
        return next
          .handle(modifiedReq)
      })
    ).pipe(catchError(x=> this.handleAuthError(x)));; //here use an arrow function, otherwise you may get "Cannot read property 'navigate' of undefined" on angular 4.4.2/net core 2/webpack 2.70
  }

  private getShop(): Observable<any> {
    return this.store.select('shop');
  }
}

