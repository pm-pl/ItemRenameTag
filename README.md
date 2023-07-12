# ItemRenameTag

[![](https://poggit.pmmp.io/shield.state/ItemRenameTag)](https://poggit.pmmp.io/p/ItemRenameTag)
<a href="https://poggit.pmmp.io/p/ItemRenameTag"><img src="https://poggit.pmmp.io/shield.state/ItemRenameTag"></a [![](https://poggit.pmmp.io/shield.api/ItemRenameTag)](https://poggit.pmmp.io/p/ItemRenameTag)
<a href="https://poggit.pmmp.io/p/ItemRenameTag"><img src="https://poggit.pmmp.io/shield.api/ItemRenameTag"></a>


ItemRenameTag is a plugin for PocketMine-MP that adds a physical item to rename items in-game. Players can use the item to set a custom name for the item they are holding.

## Features

- Rename items with a custom name
- Simple and easy-to-use
- efficient

## Installation

1. Download the latest plugin release from the [Releases](https://github.com/iLVOEWOCK/ItemRenameTag/releases) page.
2. Place the downloaded plugin (`ItemRenameTag.phar`) into the `plugins` folder of your PocketMine-MP server.
3. Restart the server.

## Usage

1. Obtain an Item Rename Tag by using the `/giveitem` command.
2. Hold the item in your hand.
3. Type the desired custom name in the chat.
4. The item in your hand will be renamed with the custom name.

## Commands

- `/giveitem [amount] [player]` - Gives the player the Item Rename Tag. (Permission: `itemrenametag.giveitem`)
- `/giveitem reload` - Reloads the ItemRenameTag configuration. (Permission: `itemrenametag.reload`)

## Permissions

- `itemrenametag.giveitem` - Allows access to the `/giveitem` command. (default: op)
- `itemrenametag.reload` - Allows access to the `/giveitem reload` subcommand. (default: op)

## Configuration

The plugin configuration file `config.yml` allows you to customize various aspects of the plugin, such as the item ID, name, lore, and more.

```yaml
# ItemRenameTag Configuration

items:
  itemnametag:
    type: 'minecraft:paper'
    name: '&r&6&lItem Rename Tag &r&7(Right Click)'
    lore:
      - '&r&7Rename and customize your equipment'
    messages:
      success:
        - "&r&e&l(!) &r&eYour ITEM has been renamed to: '{item_name}&e'"
      air:
        - '&r&l&e** You must hold an item in your hand.'
      renaming-non-enchanted-item:
        - '&r&l&e** Item must be enchanted to rename.'
      activate:
        - "    &r&6&lRename-Tag Usage"
        - "&r&61. &r&7Hold the ITEM you'd like to rename."
        - "&r&62. &r&7Send the new name as a chat message &lwith & color codes&r&7."
        - "&r&63. &r&7Confirm the preview of the new name that is displayed."
```

## Issues and Suggestions

If you encounter any issues or have suggestions for improvements, please report them on the [Issue Tracker](https://github.com/iLVOEWOCK/ItemRenameTag/issues).

## To Do

- [ ] Rename Plugin
- [ ] Add Lore Modifier
- [ ] Make items HAVE to be enchanted optional
- [ ] Possibly add more ideas from my other [Plugin](https://github.com/iLVOEWOCK/AdvancedEnchantments) (Scroll wise)
