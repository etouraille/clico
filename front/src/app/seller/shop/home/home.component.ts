import { Component, OnInit } from '@angular/core';
import {Shop} from "@shared";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

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
