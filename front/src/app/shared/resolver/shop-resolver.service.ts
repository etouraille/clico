import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import {switchMap, take, tap} from 'rxjs/operators';
import {HttpClient} from "@angular/common/http";
import {Shop} from "../model";
import { Store } from '@ngrx/store';
import {setShop} from "../action";


@Injectable({
  providedIn: 'root',
})
export class ShopResolverService implements Resolve<Shop> {

  uuid: string;

  constructor(private http: HttpClient, private store: Store) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Shop> {
    this.uuid = route.paramMap.get('uuid').split('?')[0];

    return this.http.get<Shop>('/api/shop/' + this.uuid).pipe(
      take(1),
      tap((shop: Shop) => this.store.dispatch(setShop({shop})))
      );
  }
}
