import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import {map, switchMap, take, tap} from 'rxjs/operators';
import {HttpClient} from "@angular/common/http";
import {Shop} from "../model";
import { Store } from '@ngrx/store';
import {setShop} from "../action";


@Injectable({
  providedIn: 'root',
})
export class CurrentShopResolverService implements Resolve<Shop> {

  uuid: string;

  constructor(private http: HttpClient, private store: Store) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Shop|null> {
    return this.http.get<Shop[]>('/api/shops').pipe(
      map((shops:Shop[]) => shops[0]),
      tap(shop => shop? this.store.dispatch(setShop({shop})) : null)
    )
  }
}
