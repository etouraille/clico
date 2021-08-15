import { Component, OnInit } from '@angular/core';
import {Router} from "@angular/router";
import {PositionService, Product, ProductService} from "@shared";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {


  products: Product[] = [];

  constructor(private router: Router, private service: PositionService, private productService: ProductService) { }


  ngOnInit(): void {
    this.getPosition();
    this.setProducts();
  }

  goSeller() : void {
    this.router.navigate(['je-vends']);
  }

  getPosition() {
    navigator.geolocation.watchPosition((position) => {
      this.service.setPosition(position.coords.latitude, position.coords.longitude);
    });
  }

  setProducts(): void {
    this.productService.findProductsAround().subscribe(data => {
      data.forEach(elem => {
        this.products.push(elem[0]);
      })
    });
  }

  goShop(uuid) {
    this.router.navigate(['/boutique/' + uuid]);
  }

  goProduct(uuid) {
    this.router.navigate(['/produit/' + uuid]);
  }
}
