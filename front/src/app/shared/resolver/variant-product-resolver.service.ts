import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import {switchMap, take, tap} from 'rxjs/operators';
import {HttpClient} from "@angular/common/http";
import {Shop, Variant, VariantProduct} from "../model";
import { Store } from '@ngrx/store';
import {setShop} from "../action";


@Injectable({
  providedIn: 'root',
})
export class VariantProductResolverService implements Resolve<VariantProduct[]> {

  uuid: string;

  constructor(private http: HttpClient) {}

  resolve(route: ActivatedRouteSnapshot): Observable<VariantProduct[]> {
    this.uuid = route.paramMap.get('vuuid').split('?')[0];

    return this.http.get<VariantProduct[]>('/api/variant-product/' + this.uuid).pipe(
      take(1),
    );
  }
}
