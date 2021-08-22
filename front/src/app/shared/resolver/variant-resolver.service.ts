import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import {switchMap, take, tap} from 'rxjs/operators';
import {HttpClient} from "@angular/common/http";
import {Shop, Variant} from "../model";
import { Store } from '@ngrx/store';
import {setShop} from "../action";


@Injectable({
  providedIn: 'root',
})
export class VariantResolverService implements Resolve<Variant[]> {

  uuid: string;

  constructor(private http: HttpClient) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Variant[]> {
    this.uuid = route.paramMap.get('puuid').split('?')[0];

    return this.http.get<Variant[]>('/api/variant').pipe(
      take(1),
    );
  }
}
