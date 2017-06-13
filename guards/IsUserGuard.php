<?php
    
    class IsUserGuard implements IGuard {
    
            public function resolve(array $data) {
                return true;
            }

    }
    