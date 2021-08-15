import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs";
import {Product} from "../model";
import {map} from "rxjs/operators";
import {PositionService} from "./position.service";

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  constructor(private http: HttpClient, private positionService: PositionService ) { }

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

  findProductsAround() {
    return this.http.get<Product[]>('/product', {
      params : new HttpParams()
        .set('lat', this.positionService.getPosition().lat)
        .set('lng', this.positionService.getPosition().lng)
    })
  }
}
