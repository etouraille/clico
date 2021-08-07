import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs";
import {Product} from "../model";
import {map} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  constructor(private http: HttpClient) { }

  findProducts(
    uuid:string, filter = '', orderBy = 'asc',
    pageNumber = 0, pageSize = 3):  Observable<Product[]> {

    return this.http.get<Product[]>('/api/shop/' + uuid +'/product', {
      params: new HttpParams()
        .set('filter', filter)
        .set('orderBy', orderBy)
        .set('pageNumber', pageNumber.toString())
        .set('pageSize', pageSize.toString())
    }).pipe(
      map(res =>  res)
    );
  }
}
