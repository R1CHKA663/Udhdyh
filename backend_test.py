#!/usr/bin/env python3
"""
EASY MONEY Casino Platform Backend API Testing
Tests all backend functionality including games, auth, payments, admin panel
"""

import requests
import sys
import json
from datetime import datetime
from typing import Dict, Any, Optional

class EasyMoneyAPITester:
    def __init__(self, base_url="https://telebet-gaming.preview.emergentagent.com"):
        self.base_url = base_url
        self.api_url = f"{base_url}/api"
        self.token = None
        self.admin_token = None
        self.user_data = None
        self.tests_run = 0
        self.tests_passed = 0
        self.failed_tests = []
        
    def log_result(self, test_name: str, success: bool, details: str = ""):
        """Log test result"""
        self.tests_run += 1
        if success:
            self.tests_passed += 1
            print(f"✅ {test_name} - PASSED {details}")
        else:
            self.failed_tests.append(f"{test_name}: {details}")
            print(f"❌ {test_name} - FAILED {details}")
    
    def make_request(self, method: str, endpoint: str, data: Dict = None, 
                    headers: Dict = None, use_admin: bool = False) -> tuple:
        """Make HTTP request with proper headers"""
        url = f"{self.api_url}/{endpoint.lstrip('/')}"
        
        default_headers = {'Content-Type': 'application/json'}
        if headers:
            default_headers.update(headers)
            
        # Add auth token
        if use_admin and self.admin_token:
            default_headers['Authorization'] = f'Bearer {self.admin_token}'
        elif self.token:
            default_headers['Authorization'] = f'Bearer {self.token}'
        
        try:
            if method.upper() == 'GET':
                response = requests.get(url, headers=default_headers, timeout=30)
            elif method.upper() == 'POST':
                response = requests.post(url, json=data, headers=default_headers, timeout=30)
            elif method.upper() == 'PUT':
                response = requests.put(url, json=data, headers=default_headers, timeout=30)
            else:
                return False, {"error": f"Unsupported method: {method}"}
                
            return True, {
                "status_code": response.status_code,
                "data": response.json() if response.content else {},
                "headers": dict(response.headers)
            }
        except requests.exceptions.RequestException as e:
            return False, {"error": str(e)}
        except json.JSONDecodeError:
            return False, {"error": "Invalid JSON response"}
    
    def test_demo_auth(self):
        """Test demo authentication"""
        print("\n🔐 Testing Demo Authentication...")
        
        success, response = self.make_request('POST', '/auth/demo', {
            'username': f'test_user_{datetime.now().strftime("%H%M%S")}',
            'ref_code': None
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success') and data.get('token') and data.get('user'):
                self.token = data['token']
                self.user_data = data['user']
                self.log_result("Demo Authentication", True, 
                              f"Balance: {self.user_data.get('balance', 0)}₽")
                return True
            else:
                self.log_result("Demo Authentication", False, "Missing token or user data")
        else:
            self.log_result("Demo Authentication", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_user_profile(self):
        """Test getting user profile"""
        print("\n👤 Testing User Profile...")
        
        success, response = self.make_request('GET', '/auth/me')
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success') and data.get('user'):
                self.log_result("Get User Profile", True, 
                              f"User: {data['user'].get('name', 'Unknown')}")
                return True
        
        self.log_result("Get User Profile", False, 
                       f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_mines_game(self):
        """Test Mines game functionality"""
        print("\n💣 Testing Mines Game...")
        
        # Start game
        success, response = self.make_request('POST', '/games/mines/play', {
            'bet': 10,
            'bombs': 5
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                self.log_result("Mines - Start Game", True, f"Game started with bet 10₽")
                
                # Check current game
                success, response = self.make_request('GET', '/games/mines/current')
                if success and response['status_code'] == 200:
                    current_data = response['data']
                    if current_data.get('success') and current_data.get('active'):
                        self.log_result("Mines - Get Current Game", True, "Active game found")
                        
                        # Press a cell
                        success, response = self.make_request('POST', '/games/mines/press', {
                            'cell': 1
                        })
                        if success and response['status_code'] == 200:
                            press_data = response['data']
                            if press_data.get('success'):
                                status = press_data.get('status', 'unknown')
                                self.log_result("Mines - Press Cell", True, f"Result: {status}")
                                
                                # If still active, try to take winnings
                                if status == 'continue':
                                    success, response = self.make_request('POST', '/games/mines/take')
                                    if success and response['status_code'] == 200:
                                        take_data = response['data']
                                        if take_data.get('success'):
                                            win = take_data.get('win', 0)
                                            self.log_result("Mines - Take Winnings", True, f"Won: {win}₽")
                                        else:
                                            self.log_result("Mines - Take Winnings", False, "Failed to take")
                                return True
                            else:
                                self.log_result("Mines - Press Cell", False, "Press failed")
                        else:
                            self.log_result("Mines - Press Cell", False, 
                                          f"Status: {response.get('status_code', 'N/A')}")
                    else:
                        self.log_result("Mines - Get Current Game", False, "No active game")
                else:
                    self.log_result("Mines - Get Current Game", False, 
                                  f"Status: {response.get('status_code', 'N/A')}")
            else:
                self.log_result("Mines - Start Game", False, "Game start failed")
        else:
            self.log_result("Mines - Start Game", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_dice_game(self):
        """Test Dice game functionality"""
        print("\n🎲 Testing Dice Game...")
        
        success, response = self.make_request('POST', '/games/dice/play', {
            'bet': 10,
            'chance': 50,
            'direction': 'down'
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                result = data.get('result', 0)
                status = data.get('status', 'unknown')
                win = data.get('win', 0)
                self.log_result("Dice Game", True, 
                              f"Result: {result}, Status: {status}, Win: {win}₽")
                return True
            else:
                self.log_result("Dice Game", False, "Game failed")
        else:
            self.log_result("Dice Game", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_bubbles_game(self):
        """Test Bubbles game functionality"""
        print("\n🫧 Testing Bubbles Game...")
        
        success, response = self.make_request('POST', '/games/bubbles/play', {
            'bet': 10,
            'target': 2.0
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                result = data.get('result', 0)
                status = data.get('status', 'unknown')
                win = data.get('win', 0)
                self.log_result("Bubbles Game", True, 
                              f"Result: {result}x, Status: {status}, Win: {win}₽")
                return True
            else:
                self.log_result("Bubbles Game", False, "Game failed")
        else:
            self.log_result("Bubbles Game", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_referral_system(self):
        """Test referral system"""
        print("\n👥 Testing Referral System...")
        
        success, response = self.make_request('GET', '/ref/stats')
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                ref_link = data.get('ref_link', '')
                referalov = data.get('referalov', 0)
                income = data.get('income', 0)
                self.log_result("Referral Stats", True, 
                              f"Link: {ref_link}, Refs: {referalov}, Income: {income}₽")
                return True
            else:
                self.log_result("Referral Stats", False, "Failed to get stats")
        else:
            self.log_result("Referral Stats", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_cashback_system(self):
        """Test cashback (raceback) system"""
        print("\n💰 Testing Cashback System...")
        
        success, response = self.make_request('GET', '/bonus/raceback')
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                raceback = data.get('raceback', 0)
                self.log_result("Get Cashback", True, f"Available: {raceback}₽")
                return True
            else:
                self.log_result("Get Cashback", False, "Failed to get cashback")
        else:
            self.log_result("Get Cashback", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_payment_system(self):
        """Test payment system (mock)"""
        print("\n💳 Testing Payment System...")
        
        # Create payment
        success, response = self.make_request('POST', '/payment/create', {
            'amount': 100,
            'system': 'freekassa',
            'promo_code': None
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                payment_id = data.get('payment_id')
                self.log_result("Create Payment", True, f"Payment ID: {payment_id}")
                
                # Complete mock payment
                success, response = self.make_request('POST', f'/payment/mock/complete/{payment_id}')
                if success and response['status_code'] == 200:
                    complete_data = response['data']
                    if complete_data.get('success'):
                        amount = complete_data.get('amount', 0)
                        self.log_result("Complete Payment", True, f"Amount: {amount}₽")
                        
                        # Check payment history
                        success, response = self.make_request('GET', '/payment/history')
                        if success and response['status_code'] == 200:
                            history_data = response['data']
                            if history_data.get('success'):
                                payments = history_data.get('payments', [])
                                self.log_result("Payment History", True, f"Found {len(payments)} payments")
                                return True
                            else:
                                self.log_result("Payment History", False, "Failed to get history")
                        else:
                            self.log_result("Payment History", False, 
                                          f"Status: {response.get('status_code', 'N/A')}")
                    else:
                        self.log_result("Complete Payment", False, "Completion failed")
                else:
                    self.log_result("Complete Payment", False, 
                                  f"Status: {response.get('status_code', 'N/A')}")
            else:
                self.log_result("Create Payment", False, "Creation failed")
        else:
            self.log_result("Create Payment", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_promo_system(self):
        """Test promo code system"""
        print("\n🎫 Testing Promo System...")
        
        # Try to activate a non-existent promo
        success, response = self.make_request('POST', '/promo/activate?code=TESTPROMO')
        
        # This should fail (404) which is expected
        if success and response['status_code'] == 404:
            self.log_result("Promo Activation", True, "Correctly rejected invalid promo")
            return True
        elif success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                reward = data.get('reward', 0)
                self.log_result("Promo Activation", True, f"Activated! Reward: {reward}₽")
                return True
        
        self.log_result("Promo Activation", False, 
                       f"Unexpected response: {response.get('status_code', 'N/A')}")
        return False
    
    def test_game_history(self):
        """Test game history"""
        print("\n📊 Testing Game History...")
        
        success, response = self.make_request('GET', '/history/recent?limit=10')
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                history = data.get('history', [])
                self.log_result("Game History", True, f"Found {len(history)} games")
                return True
            else:
                self.log_result("Game History", False, "Failed to get history")
        else:
            self.log_result("Game History", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_social_links(self):
        """Test social links"""
        print("\n🔗 Testing Social Links...")
        
        success, response = self.make_request('GET', '/social')
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                social = data.get('social', {})
                telegram = social.get('telegram', '')
                if 'easymoneycaspro' in telegram:
                    self.log_result("Social Links", True, f"Telegram: {telegram}")
                    return True
                else:
                    self.log_result("Social Links", False, "Wrong Telegram link")
            else:
                self.log_result("Social Links", False, "Failed to get links")
        else:
            self.log_result("Social Links", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_admin_login(self):
        """Test admin panel login"""
        print("\n🔐 Testing Admin Login...")
        
        success, response = self.make_request('POST', '/admin/login', {
            'password': 'easymoney2025admin'
        })
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success') and data.get('token'):
                self.admin_token = data['token']
                self.log_result("Admin Login", True, "Successfully logged in")
                return True
            else:
                self.log_result("Admin Login", False, "Missing token")
        else:
            self.log_result("Admin Login", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_admin_stats(self):
        """Test admin statistics"""
        print("\n📈 Testing Admin Stats...")
        
        success, response = self.make_request('GET', '/admin/stats', use_admin=True)
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                payments = data.get('payments', {})
                users = data.get('users', {})
                bank = data.get('bank', {})
                self.log_result("Admin Stats", True, 
                              f"Users: {users.get('all', 0)}, Bank: {bank.get('dice', 0)}₽")
                return True
            else:
                self.log_result("Admin Stats", False, "Failed to get stats")
        else:
            self.log_result("Admin Stats", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_admin_users(self):
        """Test admin users management"""
        print("\n👥 Testing Admin Users...")
        
        success, response = self.make_request('GET', '/admin/users?page=1&limit=10', use_admin=True)
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                users = data.get('users', [])
                total = data.get('total', 0)
                self.log_result("Admin Users", True, f"Found {len(users)}/{total} users")
                return True
            else:
                self.log_result("Admin Users", False, "Failed to get users")
        else:
            self.log_result("Admin Users", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def test_admin_promos(self):
        """Test admin promo management"""
        print("\n🎫 Testing Admin Promos...")
        
        success, response = self.make_request('GET', '/admin/promos', use_admin=True)
        
        if success and response['status_code'] == 200:
            data = response['data']
            if data.get('success'):
                promos = data.get('promos', [])
                self.log_result("Admin Promos", True, f"Found {len(promos)} promos")
                return True
            else:
                self.log_result("Admin Promos", False, "Failed to get promos")
        else:
            self.log_result("Admin Promos", False, 
                          f"Status: {response.get('status_code', 'N/A')}")
        return False
    
    def run_all_tests(self):
        """Run all backend tests"""
        print("🚀 Starting EASY MONEY Backend API Tests...")
        print(f"🌐 Testing against: {self.base_url}")
        
        # Authentication tests
        if not self.test_demo_auth():
            print("❌ Demo auth failed - stopping tests")
            return False
            
        self.test_user_profile()
        
        # Game tests
        self.test_mines_game()
        self.test_dice_game()
        self.test_bubbles_game()
        
        # Feature tests
        self.test_referral_system()
        self.test_cashback_system()
        self.test_payment_system()
        self.test_promo_system()
        self.test_game_history()
        self.test_social_links()
        
        # Admin tests
        if self.test_admin_login():
            self.test_admin_stats()
            self.test_admin_users()
            self.test_admin_promos()
        
        # Print summary
        print(f"\n📊 Test Summary:")
        print(f"✅ Passed: {self.tests_passed}/{self.tests_run}")
        print(f"❌ Failed: {len(self.failed_tests)}/{self.tests_run}")
        
        if self.failed_tests:
            print(f"\n❌ Failed Tests:")
            for failure in self.failed_tests:
                print(f"  - {failure}")
        
        success_rate = (self.tests_passed / self.tests_run * 100) if self.tests_run > 0 else 0
        print(f"\n🎯 Success Rate: {success_rate:.1f}%")
        
        return success_rate >= 80  # Consider 80%+ as successful

def main():
    """Main test execution"""
    tester = EasyMoneyAPITester()
    success = tester.run_all_tests()
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())