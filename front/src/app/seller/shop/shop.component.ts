import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router, RouterStateSnapshot} from "@angular/router";
import {Shop} from "@shared";

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css']
})
export class ShopComponent implements OnInit {

  shop: Shop;
  active: boolean = true;

  constructor(private route: ActivatedRoute, private router: Router) { }

  ngOnInit(): void {
    this.getShop();
  }

  private getShop(): void {
    this.route.data.subscribe((data: {shop: Shop})=> {
      this.shop = data.shop;
    })
  }

  toggle() :void {
    this.active = !this.active;
  }

  navigate(link: string): void {
    switch( link ) {
      case 'home' :
        this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid]);
        break;
      case 'product' :
        this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/produits']);
      break;
    }

  }

}
