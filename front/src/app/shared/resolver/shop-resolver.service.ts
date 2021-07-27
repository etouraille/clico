import { Injectable } from '@angular/core';
import { Resolve, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { take } from 'rxjs/operators';
import {HttpClient} from "@angular/common/http";
import {Shop} from "../model";


@Injectable({
  providedIn: 'root',
})
export class ShopResolverService implements Resolve<Shop> {

  uuid: string;

  constructor(private http: HttpClient) {}

  resolve(route: ActivatedRouteSnapshot): Observable<Shop> {
    this.uuid = route.paramMap.get('uuid').split('?')[0];
    return this.http.get<Shop>('/api/shop/' + this.uuid).pipe(take(1));
  }
}
