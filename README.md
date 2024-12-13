# ResourceOptimizer Plugin for PocketMine-MP

## Description

The `ResourceOptimizer` plugin helps optimize resource usage on PocketMine-MP servers by managing memory, entities, and resources dynamically. It helps reduce lag caused by excess entities and resource usage by periodically cleaning up unused resources and removing inactive entities.

### Features:
- Clean up unused resources periodically.
- Monitor and manage the number of entities in the world.
- Automatically clean up resources if memory usage exceeds the specified limit.
- Remove inactive or non-moving entities to free up resources.
- Customizable configuration for different limits and behavior.

---

## Installation

1. Download the latest version of the plugin from the releases section of this repository.
2. Place the `ResourceOptimizer` plugin folder in the `plugins/` directory of your PocketMine-MP server.
3. Start or restart your server to load the plugin.

---

Usage

Once installed, the plugin runs automatically based on the configuration settings. It will:
Monitor the number of entities in the world.
Check memory usage of the world.
Clean up unused resources.
Remove excess or inactive entities if the limits are exceeded.
You can check the plugin's logs to see when actions are taken, such as when excess entities are removed or when memory usage exceeds the limit.

---

## Configuration

The plugin uses a `config.yml` file that can be customized to suit your needs. Below is the default configuration file:

### **config.yml**

```yaml
resource_cleanup_interval: 300 # Seconds, interval to clean up unused resources
max_entity_count: 1000 # Maximum number of entities allowed in the world before warning
max_world_memory_usage: 512 # MB, memory usage threshold for the world before warning
enable_auto_cleanup: true # Enable automatic cleanup if resource usage exceeds limits
cleanup_inactive_entities: true # Remove inactive or non-moving entities
