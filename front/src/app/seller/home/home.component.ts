import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {Shop} from "@shared";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  currentShop?: Shop;

  constructor(
    private router: Router,
    private http: HttpClient,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.setCurrentShop();
  }

  createShop() : void {
    this.router.navigate(['je-vends/je-creee-ma-boutique']);
  }

  goShop() : void {
    this.router.navigate(['je-vends/ma-boutique/' + this.currentShop.uuid])
  }

  setCurrentShop(): void {
    this.route.data.subscribe((data: { shop: Shop}) => {
      this.currentShop = data.shop;
    })
  }
}
