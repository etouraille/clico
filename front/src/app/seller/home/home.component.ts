import { Component, OnInit } from '@angular/core';
import {Router} from "@angular/router";
import {Shop} from "@shared";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  currentShop?: Shop;

  constructor(private router: Router, private http: HttpClient) { }

  ngOnInit(): void {
    this.setCurrentShop();
  }

  createShop() : void {
    this.router.navigate(['je-vends/je-creee-ma-boutique']);
  }

  goShop() : void {
    this.router.navigate(['je-vends/ma-boutique/' + this.currentShop.uuid])
  }

  setCurrentShop() : void {
    this.http.get<Shop[]>('/api/shops').subscribe((shops: Shop[]) => {
      this.currentShop = shops.length>0 ?shops[0] : null;
    })
  }
}
