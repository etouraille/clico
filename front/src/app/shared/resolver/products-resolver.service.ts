import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { take, tap} from 'rxjs/operators';
import { HttpClient } from "@angular/common/http";
import { Product } from '../model';
import { Store } from '@ngrx/store';
import { addProducts } from '../action';


@Injectable({
  providedIn: 'root',
})
export class ProductsResolverService implements Resolve<Product[]> {

  uuid: string;

  constructor(private http: HttpClient, private store: Store) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Product[]> {
    this.uuid = route.paramMap.get('uuid').split('?')[0];

    return this.http.get<Product[]>('/api/shop/' + this.uuid +'/product' ).pipe(
      take(1),
      tap((products: Product[]) => {
        this.store.dispatch(addProducts({products}))
      })
    );
  }
}
