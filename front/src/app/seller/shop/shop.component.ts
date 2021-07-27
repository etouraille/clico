import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {Shop} from "@shared";

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css']
})
export class ShopComponent implements OnInit {

  shop: Shop;

  constructor(private route: ActivatedRoute) { }

  ngOnInit(): void {
    this.getShop();
  }

  private getShop(): void {
    this.route.data.subscribe((data: {shop: Shop})=> {
      this.shop = data.shop;
    })
  }

}
