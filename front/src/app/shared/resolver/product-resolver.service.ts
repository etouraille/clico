import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { take, tap} from 'rxjs/operators';
import { HttpClient } from "@angular/common/http";
import { Product } from '../model';
import { Store } from '@ngrx/store';
import { addProduct } from './../action';


@Injectable({
  providedIn: 'root',
})
export class ProductResolverService implements Resolve<Product> {

  uuid: string;

  constructor(private http: HttpClient, private store: Store) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Product> {
    this.uuid = route.paramMap.get('puuid').split('?')[0];

    return this.http.get<Product>('/api/product/' + this.uuid).pipe(
      take(1),
      tap((product: Product) => this.store.dispatch(addProduct({product})))
    );
  }
}
