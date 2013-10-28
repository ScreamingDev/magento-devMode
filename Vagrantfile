Vagrant.configure("2") do |config|

  # Forward ssh agent
  config.ssh.forward_agent = true

  # You may need to install: vagrant plugin install vagrant-hostmanager
  # Let you manage the guests /etc/hosts settings
  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.ignore_private_ip = false
  config.hostmanager.include_offline = true

  # define a dev environment for guest
  config.vm.define :lucid do |dev_config|
    # using the lucid32 box: vagrant box add lucid32 http://files.vagrantup.com/lucid32.box
    dev_config.vm.box = "lucid32"
    dev_config.vm.hostname = "lemike-devmode"
    dev_config.vm.network :private_network, ip: "10.11.12.18"
    dev_config.vm.synced_folder ".", "/vagrant"

    # set guest hardware limits
    dev_config.vm.provider :virtualbox do |vb|
      vb.customize ["modifyvm", :id, "--memory", "1024", "--cpus", "1", "--pae", "on", "--hwvirtex", "on"]
    end

    # dev_config.vm.provision "shell", path: "setup-guest.sh"
    dev_config.hostmanager.aliases = %w(lemike-devmode.dev www.lemike-devmode.dev)
  end

end
