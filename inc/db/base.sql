--
-- Estrutura da tabela `wp_sigsa_bascliente`
--

CREATE TABLE `wp_sigsa_bascliente` (
  `IDCliente` int(11) NOT NULL ,
  `NUCnpjCliente` varchar(15) NOT NULL,
  `NMRazaoCliente` varchar(50) NOT NULL,
  `NMFantasiaCliente` varchar(50) DEFAULT NULL,
  `EDWebsiteCliente` varchar(45) DEFAULT NULL,
  `IMLogoCliente` int(11) DEFAULT NULL,
  `STCliente` int(1) DEFAULT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `wp_sigsa_bascliente`
--

INSERT INTO `wp_sigsa_bascliente` (`IDCliente`, `NUCnpjCliente`, `NMRazaoCliente`, `NMFantasiaCliente`, `EDWebsiteCliente`, `IMLogoCliente`, `STCliente`, `IDEmpresa`) VALUES
(1, '12154545654654', 'Cliente 1', 'Nome fantasia 1', 'www.teste.com', 554, 0, 1),
(2, '25465465484845', 'Cliente 2', 'Nome fantasia 2', 'www.teste.com', 554, 1, 1),
(3, '33345434534534', 'Cliente 3', 'Nome fantasia 3', 'www.teste.com', 554, 1, 1),
(4, '11999999999999', 'Cliente 4', 'Nome fantasia 4', 'www.teste.com', 149, 0, 1),
(5, '33345434534534', 'Cliente 5', 'Nome fantasia 5', 'www.teste.com', 554, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_basenderecocliente`
--

CREATE TABLE `wp_sigsa_basenderecocliente` (
  `IDEnderecoCliente` int(11) NOT NULL,
  `CDTipoEnderecoCliente` int(11) NOT NULL,
  `DSEnderecoClienteJSON` text NOT NULL,
  `NULogradouroEndCliente` varchar(10) DEFAULT NULL,
  `DSComplementoEndCliente` varchar(45) DEFAULT NULL,
  `DSTelefoneEndCliente` text NOT NULL,
  `IDCliente` int(11) NOT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_gerhistorico`
--

CREATE TABLE IF NOT EXISTS `wp_sigsa_gerhistorico` (
  `IDHistorico` int(11) NOT NULL,
  `DSTipoAcaoHistorico` varchar(50) NOT NULL,
  `DTHistorico` datetime NOT NULL,
  `IDUsuarioHistorico` bigint(20) NOT NULL,
  `DSHistoricoJSON` text NOT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_germetadado`
--

CREATE TABLE `wp_sigsa_germetadado` (
  `IDMetadado` int(10) UNSIGNED NOT NULL,
  `NMEntidadeMetadado` varchar(45) DEFAULT NULL,
  `NMCampoMetadado` varchar(45) NOT NULL,
  `DSOpcaoMetadado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `wp_sigsa_germetadado`
--

INSERT INTO `wp_sigsa_germetadado` (`IDMetadado`, `NMEntidadeMetadado`, `NMCampoMetadado`, `DSOpcaoMetadado`) VALUES
(21, NULL, 'CDTipoContratoEmpresa', 'Original'),
(22, NULL, 'CDTipoContratoEmpresa', 'Aditivo contratual'),
(23, NULL, 'CDTipoContratoEmpresa', 'Concessão de uso'),
(24, NULL, 'CDSituacaoContratoEmpresa', 'Em execução'),
(25, NULL, 'CDSituacaoContratoEmpresa', 'Paralisado'),
(26, NULL, 'CDSituacaoContratoEmpresa', 'Finalizado'),
(27, NULL, 'CDTipoEndEmpresa', 'Administrativo'),
(28, NULL, 'CDTipoEndEmpresa', 'Contato'),
(29, NULL, 'CDTipoEndEmpresa', 'Correspondência'),
(30, NULL, 'CDTipoEndEmpresa', 'Financeiro'),
(31, NULL, 'CDTipoEndEmpresa', 'Geral'),
(32, NULL, 'CDTipoEndEmpresa', 'Pedagógico'),
(33, NULL, 'CDTipoEndEmpresa', 'TI'),
(42, NULL, 'DSReferenciaContatoEmpJSON', 'Administrativo'),
(43, NULL, 'DSReferenciaContatoEmpJSON', 'Compras'),
(44, NULL, 'DSReferenciaContatoEmpJSON', 'Contratos'),
(45, NULL, 'DSReferenciaContatoEmpJSON', 'Gestão'),
(46, NULL, 'DSReferenciaContatoEmpJSON', 'Jurídico'),
(47, NULL, 'DSReferenciaContatoEmpJSON', 'Outros'),
(48, NULL, 'DSReferenciaContatoEmpJSON', 'Pedagógico'),
(49, NULL, 'DSReferenciaContatoEmpJSON', 'TI');

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_parcontatoempresa`
--

CREATE TABLE 'wp_sigsa_parcontatoempresa' (
  `IDContatoEmpresa` int(11) NOT NULL,
  `NMContatoEmpresa` varchar(100) NOT NULL,
  `DSSetorContatoEmpresa` varchar(100) NOT NULL,
  `DSCargoContatoEmpresa` varchar(50) NOT NULL,
  `DSReferenciaContatoEmpJSON` varchar(100) NOT NULL,
  `EDEmailContatoEmpresa` varchar(100) NOT NULL,
  `DSTelefoneContatoEmpresaJSON` varchar(200) DEFAULT NULL,
  `IDEnderecoEmpresa` int(11) DEFAULT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `wp_sigsa_parcontatoempresa`
--

INSERT INTO `wp_sigsa_parcontatoempresa` (`IDContatoEmpresa`, `NMContatoEmpresa`, `DSSetorContatoEmpresa`, `DSCargoContatoEmpresa`, `DSReferenciaContatoEmpJSON`, `EDEmailContatoEmpresa`, `DSTelefoneContatoEmpresaJSON`, `IDEnderecoEmpresa`, `IDEmpresa`) VALUES
(1, 'Moacyr Leandro Delboni Loss', 'Setor da empresa', 'Cargo na empresa', '[\"42\",\"44\",\"48\",\"49\"]', 'moaloss@gmail.com', '[{\"numero\":\"(31) 99111-6139\",\"tipo\":\"Whatsapp\"},{\"numero\":\"(27) 99730-8051\",\"tipo\":\"Celular\"},{\"numero\":\"(43) 3444-4455\",\"tipo\":\"Whatsapp\"}]', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_parcontratoempresa`
--

CREATE TABLE `wp_sigsa_parcontratoempresa` (
  `IDContratoEmpresa` int(11) NOT NULL,
  `CDTipoContratoEmpresa` int(10) NOT NULL,
  `CDContratoEmpresa` varchar(30) NOT NULL,
  `DSContratoEmpresa` text NOT NULL,
  `DTInicioContratoEmpresa` date NOT NULL,
  `DTTerminoContratoEmpresa` date NOT NULL,
  `CDSituacaoContratoEmpresa` int(10) NOT NULL,
  `NUAditivoContratoEmpresa` varchar(30) DEFAULT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `wp_sigsa_parcontratoempresa`
--

INSERT INTO `wp_sigsa_parcontratoempresa` (`IDContratoEmpresa`, `CDTipoContratoEmpresa`, `CDContratoEmpresa`, `DSContratoEmpresa`, `DTInicioContratoEmpresa`, `DTTerminoContratoEmpresa`, `CDSituacaoContratoEmpresa`, `NUAditivoContratoEmpresa`, `IDEmpresa`) VALUES
(1, 23, '2222', 'Primeiro Contrato Aditivo', '2021-07-01', '2021-07-28', 24, '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_parempresa`
--

CREATE TABLE `wp_sigsa_parempresa` (
  `IDEmpresa` int(11) NOT NULL,
  `NUCnpjEmpresa` varchar(20) NOT NULL,
  `NMRazaoEmpresa` varchar(50) NOT NULL,
  `NMFantasiaEmpresa` varchar(50) DEFAULT NULL,
  `NMWebsiteEmpresa` varchar(50) DEFAULT NULL,
  `IMLogoEmpresa` bigint(11) DEFAULT NULL,
  `STEmpresa` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `wp_sigsa_parempresa`
--

INSERT INTO `wp_sigsa_parempresa` (`IDEmpresa`, `NUCnpjEmpresa`, `NMRazaoEmpresa`, `NMFantasiaEmpresa`, `NMWebsiteEmpresa`, `IMLogoEmpresa`, `STEmpresa`) VALUES
(1, '02381997000100', 'ACTCON TECNOLOGIA LTDA', 'Grupo Actcon', 'www.actcon.com', 178, 1),
(2, '02381997000100', 'Empresa 2', 'Grupo Actcon', 'www.actcon.com', 42, 1),
(3, '02381997000100', 'Empresa 3', 'Grupo Actcon', 'www.actcon.com', 46, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wp_sigsa_parenderecoempresa`
--

CREATE TABLE `wp_sigsa_parenderecoempresa` (
  `IDEnderecoEmpresa` int(11) NOT NULL,
  `CDTipoEndEmpresaJSON` varchar(100) NOT NULL,
  `DSEnderecoEmpresaJSON` text NOT NULL,
  `NULogradouroEndEmpresa` varchar(10) DEFAULT NULL,
  `DSComplementoEndEmpresa` varchar(50) DEFAULT NULL,
  `DSTelefoneEndEmpresaJSON` text NOT NULL,
  `IDEmpresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `wp_sigsa_parenderecoempresa`
--

INSERT INTO `wp_sigsa_parenderecoempresa` (`IDEnderecoEmpresa`, `CDTipoEndEmpresaJSON`, `DSEnderecoEmpresaJSON`, `NULogradouroEndEmpresa`, `DSComplementoEndEmpresa`, `DSTelefoneEndEmpresaJSON`, `IDEmpresa`) VALUES
(1, '[\"27\",\"28\",\"29\",\"30\",\"33\"]', '{\"cep\":\"29620-000\",\"logradouro\":\"Vila palmital\",\"bairro\":\"Zona rural\",\"localidade\":\"Itarana\",\"uf\":\"ES\"}', '89', 'Perto do edinho coan', '[{\"numero\":\"(31) 99111-6139\",\"tipo\":\"Whatsapp\"}]', 1),
(4, '[\"28\"]', '{\"cep\":\"35162-563\",\"logradouro\":\"RUA MARQUES DE TAMANDAR\\u00c9\",\"bairro\":\"CIDADE NOBRE\",\"localidade\":\"IPATINGA\",\"uf\":\"MG\"}', '785', '', 'null', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `wp_sigsa_bascliente`
--
ALTER TABLE `wp_sigsa_bascliente`
  ADD PRIMARY  KEY (`IDCliente`);

--
-- Índices para tabela `wp_sigsa_basenderecocliente`
--
ALTER TABLE `wp_sigsa_basenderecocliente`
  ADD PRIMARY KEY (`IDEnderecoCliente`);

--
-- Índices para tabela `wp_sigsa_gerhistorico`
--
ALTER TABLE `wp_sigsa_gerhistorico`
  ADD PRIMARY KEY (`IDHistorico`);

--
-- Índices para tabela `wp_sigsa_germetadado`
--
ALTER TABLE `wp_sigsa_germetadado`
  ADD PRIMARY KEY (`IDMetadado`);

--
-- Índices para tabela `wp_sigsa_parcontatoempresa`
--
ALTER TABLE `wp_sigsa_parcontatoempresa`
  ADD PRIMARY KEY (`IDContatoEmpresa`);

--
-- Índices para tabela `wp_sigsa_parcontratoempresa`
--
ALTER TABLE `wp_sigsa_parcontratoempresa`
  ADD PRIMARY KEY (`IDContratoEmpresa`);

--
-- Índices para tabela `wp_sigsa_parempresa`
--
ALTER TABLE `wp_sigsa_parempresa`
  ADD PRIMARY KEY (`IDEmpresa`);

--
-- Índices para tabela `wp_sigsa_parenderecoempresa`
--
ALTER TABLE `wp_sigsa_parenderecoempresa`
  ADD PRIMARY KEY (`IDEnderecoEmpresa`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `wp_sigsa_bascliente`
--
ALTER TABLE `wp_sigsa_bascliente`
  MODIFY `IDCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_basenderecocliente`
--
ALTER TABLE `wp_sigsa_basenderecocliente`
  MODIFY `IDEnderecoCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_gerhistorico`
--
ALTER TABLE `wp_sigsa_gerhistorico`
  MODIFY `IDHistorico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_germetadado`
--
ALTER TABLE `wp_sigsa_germetadado`
  MODIFY `IDMetadado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_parcontatoempresa`
--
ALTER TABLE `wp_sigsa_parcontatoempresa`
  MODIFY `IDContatoEmpresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_parcontratoempresa`
--
ALTER TABLE `wp_sigsa_parcontratoempresa`
  MODIFY `IDContratoEmpresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_parempresa`
--
ALTER TABLE `wp_sigsa_parempresa`
  MODIFY `IDEmpresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `wp_sigsa_parenderecoempresa`
--
ALTER TABLE `wp_sigsa_parenderecoempresa`
  MODIFY `IDEnderecoEmpresa` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
